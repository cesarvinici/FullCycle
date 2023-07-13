import AddProductUseCase from "./add-product.usecase";

const mockProductRepository = () => ({
    add: jest.fn(),
    find: jest.fn()
});

describe("Add Product usecase unit tests", () => {

    it("Should add a product", async () => {
      // reositório 
      const productRepository = mockProductRepository();
      // usecase
        const usecase = new AddProductUseCase(productRepository);

        const input = {
            name: "Product 1",
            description: "Product 1 description",
            price: 10,
        }

        const result = await usecase.execute(input);
        expect(productRepository.add).toBeCalled();

        expect(result.id).toBeDefined();
        expect(result.name).toBe(input.name);
        expect(result.price).toBe(input.price);
        expect(result.createdAt).toBeDefined();
        expect(result.updatedAt).toBeDefined();
    });

});