import Id from "../../../@shared/domain/value-object/id.value-object";
import Product from "../../domain/Product.entity";
import { PlaceOrderInputDto } from "./place-order.dto";
import PlaceOrderUseCase from "./place-order.usecase";



const mockDate = new Date("2021-01-01T00:00:00.000Z")

describe("PlaceOrderUseCase unit test", () => {

    //@ts-expect-error - no paramms in constructor
    const placeOrderUsecase = new PlaceOrderUseCase();

    describe("ValidateProducts method", () => {

        it("Should throw an error if no products are selected", async () => {

            const input: PlaceOrderInputDto = {
                clientId: '0',
                products: []
            };

            await expect(
                placeOrderUsecase["validateProducts"](input)).rejects.toThrowError(new Error("No Products selected"));
        });

        it("Should trhow an error when product is out of stock", async () => {
            const mockProductFacade = {
                checkStock: jest.fn(({ id }: { id: string }) =>
                    Promise.resolve({
                        id,
                        stock: id === '1' ? 0 : 1
                    })
                )
            }

            //@ts-expect-error - force set productFacade
            placeOrderUsecase["_productFacade"] = mockProductFacade;

            let input: PlaceOrderInputDto = {
                clientId: '0',
                products: [{ productId: "1" }]
            };


            await expect(
                placeOrderUsecase["validateProducts"](input))
                .rejects
                .toThrow(new Error("Product 1 is not available in stock")
                );

            input = {
                clientId: '0',
                products: [{ productId: "0" }, { productId: "1" }]
            }

            await expect(placeOrderUsecase["validateProducts"](input))
                .rejects
                .toThrow(new Error("Product 1 is not available in stock"));


            expect(mockProductFacade.checkStock).toBeCalledTimes(3);

            input = {
                clientId: '0',
                products: [{ productId: "0" }, { productId: "1" }, { productId: "2" }]
            }

            await expect(placeOrderUsecase["validateProducts"](input))
                .rejects
                .toThrow(new Error("Product 1 is not available in stock"));
            expect(mockProductFacade.checkStock).toBeCalledTimes(5);
        });
    });

    describe("getProducts method", () => {
        beforeAll(() => {
            jest.useFakeTimers();
            jest.setSystemTime(mockDate);
        });

        afterAll(() => {
            jest.useRealTimers();
        });

        it("Should throw an error if product not found", async () => {
            const mockCatalogFacade = {
                find: jest.fn().mockResolvedValue(null)
            }

            //@ts-expect-error - force set catalogFacade
            placeOrderUsecase["_catalogFacade"] = mockCatalogFacade;

            await expect(placeOrderUsecase["getProduct"]("0"))
                .rejects
                .toThrow(new Error("Product 0 not found")
                );
        })

        it("Should return a product", async () => {
            const mockCatalogFacade = {
                find: jest.fn().mockResolvedValue({
                    id: "1",
                    name: "name",
                    salesPrice: 10,
                    description: "description",
                })
            }

            //@ts-expect-error - force set catalogFacade
            placeOrderUsecase["_catalogFacade"] = mockCatalogFacade;

            await expect(placeOrderUsecase["getProduct"]("1")).resolves.toEqual(
                new Product({
                    id: new Id("1"),
                    name: "name",
                    salesPrice: 10,
                    description: "description",
                })
            );

            expect(mockCatalogFacade.find).toBeCalledTimes(1);
        });


    })

    describe("Execute method", () => {
        beforeAll(() => {
            jest.useFakeTimers();
            jest.setSystemTime(mockDate);
        });

        afterAll(() => {
            jest.useRealTimers();
        });

        it("Should throw an error if client is not found", async () => {
            const mockClientFacade = {
                findClient: jest.fn().mockResolvedValue(null)
            }


            //@ts-expect-error - no paramms in constructor
            const placeOrderUsecase = new PlaceOrderUseCase();
            //@ts-expect-error - force set clientFacade
            placeOrderUsecase["_clientFacade"] = mockClientFacade;

            const input: PlaceOrderInputDto = {
                clientId: '0',
                products: []
            };

            await expect(placeOrderUsecase.execute(input)).rejects.toThrowError("Client not found");

        });

        it("Should throw an error if product are invalid", async () => {
            const mockClientFacade = {
                findClient: jest.fn().mockResolvedValue(true)
            }


            //@ts-expect-error - no paramms in constructor
            const placeOrderUsecase = new PlaceOrderUseCase();

            const mockValidateProducts = jest
                //@ts-expect-error - spy on private mmethod
                .spyOn(placeOrderUsecase, "validateProducts")
                //@ts-expect-error - not return never
                .mockRejectedValue(new Error("No Products selected"));

            const input: PlaceOrderInputDto = {
                clientId: '1',
                products: []
            };

            //@ts-expect-error - force set clientFacade
            placeOrderUsecase["_clientFacade"] = mockClientFacade;



            await expect(placeOrderUsecase.execute(input)).rejects.toThrowError("No Products selected");

            expect(mockValidateProducts).toBeCalledTimes(1);
        });

        describe("Place and Order", () => {
            const clientProps = {
                id: "1",
                name: "name",
                email: "email",
                document: "document",
                street: "street",
                number: "number",
                complement: "complement",
                city: "city",
                state: "state",
                zipCode: "zipCode",
            };

            const mockClientFacade = {
                findClient: jest.fn().mockResolvedValue(clientProps),
                addClient: jest.fn()
            }

            const mockPaymentFacade = {
                process: jest.fn()
            }

            const mockCheckoutRepository = {
                addOrder: jest.fn(),
                findOrder: jest.fn()
            }

            const mockInvoiceFacade = {
                generateInvoice: jest.fn().mockResolvedValue({ id: "1" }),
                findInvoice: jest.fn()
            }

            const placeOrderUsecase = new PlaceOrderUseCase(
                mockClientFacade,
                null,
                null,
                mockCheckoutRepository,
                mockInvoiceFacade,
                mockPaymentFacade
            );

            const products = {
                "1": new Product({
                    id: new Id("1"),
                    name: "product 1",
                    salesPrice: 10,
                    description: "description",
                }),
                "2": new Product({
                    id: new Id("2"),
                    name: "product 2",
                    salesPrice: 20,
                    description: "description",
                }),
            };

            const mockValidateProducts = jest
                //@ts-expect-error - spy on private method
                .spyOn(placeOrderUsecase, "validateProducts")
                //@ts-expect-error - spy on private method
                .mockResolvedValue(null);

            const mockGetProduct = jest
                //@ts-expect-error - spy on private method
                .spyOn(placeOrderUsecase, "getProduct")
                //@ts-expect-error - not return never
                .mockImplementation((id: keyof typeof products) => products[id]);


            it("Should not be approved", async () => {
                mockPaymentFacade.process = mockPaymentFacade.process.mockReturnValue({
                    transactionId: "1t",
                    orderId: "1o",
                    amount: 30,
                    status: "error",
                    createdAt: new Date(),
                    updatedAt: new Date(),
                });

                const input: PlaceOrderInputDto = {
                    clientId: '1',
                    products: [
                        { productId: "1" },
                        { productId: "2" },
                    ]
                };

                let output = await placeOrderUsecase.execute(input);

                expect(output.invoiceId).toBe(null);
                expect(output.total).toBe(30);
                expect(output.products).toStrictEqual([
                    { productId: "1" },
                    { productId: "2" },
                ]);

                expect(mockClientFacade.findClient).toBeCalledTimes(1);
                expect(mockClientFacade.findClient).toHaveBeenCalledWith({
                    id: "1"
                });

                expect(mockValidateProducts).toHaveBeenCalledTimes(1);
                expect(mockValidateProducts).toHaveBeenCalledWith(input);
                expect(mockGetProduct).toHaveBeenCalledTimes(2);
                expect(mockCheckoutRepository.addOrder).toHaveBeenCalledTimes(1);
                expect(mockPaymentFacade.process).toHaveBeenCalledTimes(1);
                expect(mockPaymentFacade.process).toHaveBeenCalledWith({
                    orderId: output.id,
                    amount: output.total,
                });

                expect(mockInvoiceFacade.generateInvoice).toHaveBeenCalledTimes(0);

            });


            it("Should be approved", async () => {
                mockPaymentFacade.process = mockPaymentFacade.process.mockReturnValue({
                    transactionId: "1t",
                    orderId: "1o",
                    amount: 30,
                    status: "approved",
                    createdAt: new Date(),
                    updatedAt: new Date(),
                });

                const input: PlaceOrderInputDto = {
                    clientId: '1',
                    products: [
                        { productId: "1" },
                        { productId: "2" },
                    ]
                };

                let output = await placeOrderUsecase.execute(input);

                expect(output.invoiceId).toBe("1");
                expect(output.total).toBe(30);
                expect(output.products).toStrictEqual([
                    { productId: "1" },
                    { productId: "2" },
                ]);

                expect(mockClientFacade.findClient).toBeCalledTimes(1);
                expect(mockClientFacade.findClient).toHaveBeenCalledWith({
                    id: "1"
                });

                expect(mockValidateProducts).toHaveBeenCalledTimes(1);
                expect(mockValidateProducts).toHaveBeenCalledWith(input);
                expect(mockGetProduct).toHaveBeenCalledTimes(2);
                expect(mockCheckoutRepository.addOrder).toHaveBeenCalledTimes(1);
                expect(mockPaymentFacade.process).toHaveBeenCalledTimes(1);
                expect(mockPaymentFacade.process).toHaveBeenCalledWith({
                    orderId: output.id,
                    amount: output.total,
                });

                expect(mockInvoiceFacade.generateInvoice).toHaveBeenCalledTimes(1);
                expect(mockInvoiceFacade.generateInvoice).toHaveBeenCalledWith({
                    name: clientProps.name,
                    document: clientProps.document,
                    street: clientProps.street,
                    number: clientProps.number,
                    complement: clientProps.complement,
                    city: clientProps.city,
                    state: clientProps.state,
                    zipCode: clientProps.zipCode,
                    items: [
                        {
                            id: products["1"].id.id,
                            name: products["1"].name,
                            price: products["1"].salesPrice,
                        },
                        {
                            id: products["2"].id.id,
                            name: products["2"].name,
                            price: products["2"].salesPrice,
                        },
                    ]
                });

            });

        });

    });

});