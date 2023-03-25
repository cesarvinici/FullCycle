import { Sequelize } from "sequelize-typescript";
import Product from "../../../domain/product/entity/product";
import ProductModel from "../../../infrastructure/product/repository/sequilize/product.model";
import ProductRepository from "../../../infrastructure/product/repository/sequilize/product.repository";
import ListProductUsecase from "./list.product.usecases";

describe("List Product Use Case unit tests", () => {

    let sequilize: Sequelize;

    beforeEach(async () => {
        sequilize = new Sequelize({
            dialect: "sqlite",
            storage: ":memory:"
        });

        sequilize.addModels([ProductModel])
        await sequilize.sync({ force: true });

    });

    afterEach(async () => {
        await sequilize.close();
    });


    it("should list all products", async () => {
        const productRepository = new ProductRepository()
        const useCase = new ListProductUsecase(productRepository);

        const product = new Product("1", "Product 1", 1.00);
        const product2 = new Product("2", "Product 2", 2.00);
        await productRepository.create(product);
        await productRepository.create(product2);

        const output = await useCase.execute({});

        expect(output).toEqual(
            {
                products: [
                    {
                        id: product.id,
                        name: product.name,
                        price: product.price
                    },
                    {
                        id: product2.id,
                        name: product2.name,
                        price: product2.price
                    }
                ]
            }
        );
    });

});