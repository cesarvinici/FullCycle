import Id from "../../../@shared/domain/value-object/id.value-object";
import FindInvoiceUseCase from "./find-invoice.usecase";

const expectedInvoice = {
    id: new Id("1"),
    name: "John Doe",
    document: "123456789",
    address: {
        street: "Street 1",
        number: 10,
        complement: "Complement 1",
        city: "City 1",
        state: "State 1",
        zip: "12345678"
    },
    items: [
        {
            id: new Id("1"),
            name: "Product 1",
            price: 10
        },
        {
            id: new Id("2"),
            name: "Product 2",
            price: 20
        }
    ],
    total: 36,
    createdAt: new Date(),
};

const MockInvoiceRepository = () => ({
    generate: jest.fn(),
    find: jest.fn().mockResolvedValue(expectedInvoice)
});

describe("Find Invoice usecase Tests", () => {
    it("Should find an invoice", async () => {

        const repository = MockInvoiceRepository();

        const usecase = new FindInvoiceUseCase(repository);

        const input = {
            id: "1"
        }

        const result = await usecase.execute(input);

        expect(repository.find).toBeCalled();
        expect(result.id).toBe(expectedInvoice.id.id);
        expect(result.name).toBe(expectedInvoice.name);
        expect(result.document).toBe(expectedInvoice.document);
        expect(result.address).toEqual({
            street: expectedInvoice.address.street,
            number: expectedInvoice.address.number,
            complement: expectedInvoice.address.complement,
            city: expectedInvoice.address.city,
            state: expectedInvoice.address.state,
            zipCode: expectedInvoice.address.zip
        });
        
        expect(result.items).toHaveLength(2);
        expect(result.items[0]).toEqual({
            id: expectedInvoice.items[0].id.id,
            name: expectedInvoice.items[0].name,
            price: expectedInvoice.items[0].price
        });
        expect(result.items[1]).toEqual({
            id: expectedInvoice.items[1].id.id,
            name: expectedInvoice.items[1].name,
            price: expectedInvoice.items[1].price
        });
        expect(result.total).toBe(expectedInvoice.total);
        expect(result.createdAt).toBe(expectedInvoice.createdAt);
    });
});