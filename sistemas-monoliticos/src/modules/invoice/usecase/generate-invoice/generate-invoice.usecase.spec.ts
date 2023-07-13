import Product from "../../../product-adm/domain/product-adm.entity";
import GenerateInvoiceUseCase from "./generate-invoice.usecase";

const MockInvoiceRepository = () => ({
    generate: jest.fn(),
    find: jest.fn()
});


describe('Generate Invoice usecase Tests', () => {
    it("Should generate an invoice", async () => {

        const repository = MockInvoiceRepository();
        const items = [
            new Product({
                name: "Product 1",
                description: "Product 1",
                purchasePrice: 10,
                stock: 10
            }),
            new Product({
                name: "Product 2",
                description: "Product 2",
                purchasePrice: 20,
                stock: 10
            })
        ]

        const usecase = new GenerateInvoiceUseCase(repository);

        const input = {
            id: "1",
            name: "John Doe",
            document: "123456789",
            street: "Street 1",
            number: 10,
            complement: "Complement 1",
            city: "City 1",
            state: "State 1",
            zipCode: "12345678",
            items: items,
        }

        const result = await  usecase.execute(input);

        expect(repository.generate).toBeCalled();
        expect(result.id).toBeDefined();
        expect(result.name).toBe(input.name);
        expect(result.document).toBe(input.document);
        expect(result.street).toBe(input.street);
        expect(result.number).toBe(input.number);
        expect(result.city).toBe(input.city);
        expect(result.state).toBe(input.state);
        expect(result.zipCode).toBe(input.zipCode);
        expect(result.items).toHaveLength(2);
        expect(result.items[0]).toEqual({
            id: items[0].id.id,
            name: items[0].name,
            price: items[0].salesPrice
        });
        expect(result.items[1]).toEqual({
            id: items[1].id.id,
            name: items[1].name,
            price: items[1].salesPrice
        });
        expect(result.total).toBe(36);    
    });
});