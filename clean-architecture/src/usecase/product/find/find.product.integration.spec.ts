import { Sequelize } from "sequelize-typescript";
import Product from "../../../domain/product/entity/product";
import ProductModel from "../../../infrastructure/product/repository/sequilize/product.model";
import ProductRepository from "../../../infrastructure/product/repository/sequilize/product.repository";
import FindProductUsecase from "./find.product.usecase";

describe("Find Product Use Case unit tests", () => {

    let sequelize: Sequelize;

    beforeEach(async () => {
        sequelize = new Sequelize({
            dialect: "sqlite",
            storage: ":memory:",
            logging: false,
            sync: { force: true },
        });

        sequelize.addModels([ProductModel]);
        await sequelize.sync();
    });

    afterEach(async () => {
        await sequelize.close();
    });

    it("should find a product", async () => {
        const productRepository = new ProductRepository()
        const useCase = new FindProductUsecase(productRepository);
        
        const product = new Product("1", "Product 1", 1.00);

        await productRepository.create(product);


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
        const productRepository = new ProductRepository();
        const useCase = new FindProductUsecase(productRepository);

        const input = {
            id: "1"
        }

        await expect(useCase.execute(input)).rejects.toThrow("Product not found");
    });

});