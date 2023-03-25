import CreateProductUseCase from "./create.product.usercase";


const input = {
    name: "Product 1",
    price: 1.00
}

const mockProductRepository  = () => {
    return {
        create: jest.fn(),
        update: jest.fn(),
        find: jest.fn(),
        findAll: jest.fn()
    }
}

describe("Create Product Use Case unit tests", () => {
    it("should create a product", async () => {
        const productRepository = mockProductRepository();
        const useCase = new CreateProductUseCase(productRepository);

        const expectedOutput = {
            id: expect.any(String),
            name: input.name,
            price: input.price
        }

        const output = await useCase.execute(input);

        expect(output).toEqual(expectedOutput);
    });
});