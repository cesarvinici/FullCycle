import Product from "../../../domain/product/entity/product";
import UpdateProductUseCase from "./update.product.usecase";

const MockRepository  = () => {
    return {
        find: jest.fn()
        .mockReturnValue(new Product('1', 'Product 1 teste', 10)),
    findAll: jest.fn(),
    create: jest.fn(),
    update: jest.fn(),
    }
}

describe("Update Product Use Case unit tests", () => {

    it("Should update a product", async () => {
        const productRepository = MockRepository();
        const useCase = new UpdateProductUseCase(productRepository);
        
        const input = {
            id: '1',
            name: 'Product 1 updated',
            price: 10
        }
        
        const expectedOutput = {
            id: '1',
            name: 'Product 1 updated',
            price: 10
        }
        
        const output = await useCase.execute(input);
        
        expect(output).toEqual(expectedOutput);
    });


    it("Should throw and error if invalid name is provided", async () => {
        const productRepository = MockRepository();
        const useCase = new UpdateProductUseCase(productRepository);
        
        const input = {
            id: '1',
            name: '',
            price: 10
        }
        
        expect(useCase.execute(input)).rejects.toThrow("product: Name is required");
    })

    it("Should throw and error if invalid price is provided", async () => {
        
        const productRepository = MockRepository();
        const useCase = new UpdateProductUseCase(productRepository);
        
        const input = {
            id: '1',
            name: 'Product 1 updated',
            price: -10
        }
        
        expect(useCase.execute(input)).rejects.toThrow("product: Price must be greater than zero");
    });
});