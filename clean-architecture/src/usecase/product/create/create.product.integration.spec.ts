import { Sequelize } from "sequelize-typescript";
import ProductModel from "../../../infrastructure/product/repository/sequilize/product.model";
import ProductRepository from "../../../infrastructure/product/repository/sequilize/product.repository";
import CreateProductUseCase from "./create.product.usercase";

describe("Create Product Use Case integration tests", () => {
    let sequilize: Sequelize;


    beforeEach(async () => {
        sequilize = new Sequelize({
            dialect: "sqlite",
            storage: ":memory:",
            logging: false,
            sync: { force: true },
        });

        sequilize.addModels([ProductModel]);
        await sequilize.sync();
    });

    afterEach(async () => {
        await sequilize.close();
    });


    it("should create a product", async () => {

        const productRepository = new ProductRepository();
        const useCase = new CreateProductUseCase(productRepository);

        const input = {
            name: "Product 1",
            price: 1.00
        }

        const expectedOutput = {
            id: expect.any(String),
            name: input.name,
            price: input.price
        }

        const output = await useCase.execute(input);

        expect(output).toEqual(expectedOutput);
    });


});