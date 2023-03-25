import { Sequelize } from "sequelize-typescript";
import Product from "../../../domain/product/entity/product";
import ProductModel from "../../../infrastructure/product/repository/sequilize/product.model";
import ProductRepository from "../../../infrastructure/product/repository/sequilize/product.repository";
import UpdateProductUseCase from "./update.product.usecase";

describe("Update Product Use Case unit tests", () => {

    let sequelize: Sequelize

    beforeAll(async () => {
        sequelize = new Sequelize({
            dialect: "sqlite",
            storage: ":memory:",
            models: [ProductModel]
        });

        await sequelize.sync();
    });

    afterAll(async () => {
        await sequelize.close();
    });


    it("should update a product", async () => {
        const productRepository = new ProductRepository();
        const useCase = new UpdateProductUseCase(productRepository);
        
        const input = {
            id: '1',
            name: 'Product 1',
            price: 10
        }

        const product = new Product(input.id, input.name, input.price);
        await productRepository.create(product);

        input.name = 'Product 1 updated';

        const output = await useCase.execute(input);

        expect(output).toEqual(input)
    });

    it("Should throw and error if invalid name is provided", async () => {
        const productRepository = new ProductRepository();
        const useCase = new UpdateProductUseCase(productRepository);
        
        const input = {
            id: '1',
            name: '',
            price: 10
        }

        await expect(useCase.execute(input)).rejects.toThrow("Name is required");
    
    });

    it("Should throw and error if invalid price is provided", async () => {
        const productRepository = new ProductRepository();
        const useCase = new UpdateProductUseCase(productRepository);
        
        const input = {
            id: '1',
            name: 'Product 1',
            price: -1
        }

        await expect(useCase.execute(input)).rejects.toThrow("Price must be greater than zero");
    });

});