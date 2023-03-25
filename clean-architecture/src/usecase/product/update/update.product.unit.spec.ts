import Product from "../../../domain/product/entity/product";
import UpdateProductUseCase from "./update.product.usecase";

const product = new Product('1', 'Product 1', 10);

const mockRepository = () => {
    return {
        update: jest.fn(),
        create: jest.fn(),
        find: jest.fn().mockReturnValue(Promise.resolve(product)),
        findAll: jest.fn()    
    }
}

describe("Update Product Use Case unit tests", () => {

    it("should update a product", async () => {
        const productRepository = mockRepository();
        const useCase = new UpdateProductUseCase(productRepository);
        
        const input = {
            id: '1',
            name: 'Product 1 updated',
            price: 10
        }

        const output = await useCase.execute(input);

        expect(output).toEqual(input)
    });

    it("Should throw and error if invalid name is provided", async () => {
        const productRepository = mockRepository();
        const useCase = new UpdateProductUseCase(productRepository);
        
        const input = {
            id: '1',
            name: '',
            price: 10
        }

        expect(useCase.execute(input)).rejects.toThrow("Name is required");
    })

    it("Should throw and error if invalid price is provided", async () => {
        const productRepository = mockRepository();
        const useCase = new UpdateProductUseCase(productRepository);
        
        const input = {
            id: '1',
            name: 'Product 1',
            price: -1
        }

        expect(useCase.execute(input)).rejects.toThrow("Price must be greater than zero");
    });

});