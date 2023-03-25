import FindProductUsecase from "./find.product.usecase";

const mockProductRepository = () => {
    return {
        create: jest.fn(),
        update: jest.fn(),
        find: jest.fn().mockReturnValue({
            id: "1",
            name: "Product 1",
            price: 1.00
        }),
        findAll: jest.fn()
    }
}

describe("Find Product Use Case unit tests", () => {

    it("should find a product", async () => {
        const productRepository = mockProductRepository();
        const useCase = new FindProductUsecase(productRepository);

        const input = {
            id: "1"
        }

        const expectedOutput = {
            id: "1",
            name: "Product 1",
            price: 1.00
        }

        const output = await useCase.execute(input);

        expect(output).toEqual(expectedOutput);
    });

    it("should throw an error if product not found", async () => {
        const productRepository = mockProductRepository();
        productRepository.find.mockReturnValue(Promise.reject(new Error("Product not found")));
        const useCase = new FindProductUsecase(productRepository);

        const input = {
            id: "1"
        }

        await expect(useCase.execute(input)).rejects.toThrow("Product not found");
    });

});