import { Sequelize } from "sequelize-typescript";
import Product from "../domain/product.entity";
import Id from "../../@shared/domain/value-object/id.value-object";
import ProductRepository from "./product.repository";
import { ProductModel } from "./product.model";

describe("Product Repository tests", () => {

    let sequelize: Sequelize;

    beforeEach(async () => {
        sequelize = new Sequelize({
            dialect: "sqlite",
            storage: ":memory:",
            logging: false,
            sync: { force: true }
        });
        
        sequelize.addModels([ProductModel])

        await sequelize.sync();
    });

    afterEach(async () => {
        await sequelize.close();
    });


    it("Should add a product", async () => {

        const productProps = {
            id: new Id("1"),
            name: "Product 1",
            description: "Product 1 description",
            purchasePrice: 10,
            stock: 10,
        }
        const product = new Product(productProps)

        const productRepository = new ProductRepository();

        await productRepository.add(product);

        const productDb = await ProductModel.findOne(
            { where: { id: productProps.id.id } 
        });

        expect(productDb.id).toEqual(productProps.id.id);
        expect(productDb.name).toEqual(productProps.name);
        expect(productDb.description).toEqual(productProps.description);
        expect(productDb.purchasePrice).toEqual(productProps.purchasePrice);
        expect(productDb.stock).toEqual(productProps.stock);
    });


    it("Should find a product", async () => {
        const productRepository = new ProductRepository();
        ProductModel.create({
            id: "1",
            name: "Product 1",
            description: "Product 1 description",
            purchasePrice: 10,
            stock: 10,
            createdAt: new Date(),
            updatedAt: new Date(),
        });
        
        const product = await productRepository.find("1");

        expect(product.id.id).toEqual("1");
        expect(product.name).toEqual("Product 1");
        expect(product.description).toEqual("Product 1 description");
        expect(product.purchasePrice).toEqual(10);
        expect(product.stock).toEqual(10);
    });
});