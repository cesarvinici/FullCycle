import { Sequelize } from "sequelize-typescript";
import ProductModel from "../repository/product.model";
import StoreCatalogFacadeFactory from "../factory/facade.factory";

describe("Store Catalog Facade tests", () => {
    let sequelize: Sequelize;

    beforeEach(async () => {
        sequelize = new Sequelize({
            dialect: "sqlite",
            storage: ":memory:",
            logging: false,
            sync: { force: true },
        });
        sequelize.addModels([ProductModel])
        await sequelize.sync();
    });

    afterEach(async () => {
        await sequelize.close();
    });


    it("Should find all products", async () => {

        await ProductModel.create({
            id: "1",
            name: "Product 1",
            description: "Product 1 description",
            salesPrice: 10,
        });

        await ProductModel.create({
            id: "2",
            name: "Product 2",
            description: "Product 2 description",
            salesPrice: 20,
        });

        const facade = StoreCatalogFacadeFactory.create();

        const result = await facade.findAll();

        expect(result.products).toEqual([
            {
                id: "1",
                name: "Product 1",
                description: "Product 1 description",
                salesPrice: 10,
            },
            {
                id: "2",
                name: "Product 2",
                description: "Product 2 description",
                salesPrice: 20,
            }
        ]);
    });

    it("Should find a product", async () => {

        await ProductModel.create({
            id: "1",
            name: "Product 1",
            description: "Product 1 description",
            salesPrice: 10,
        });

        const facade = StoreCatalogFacadeFactory.create();

        const result = await facade.find({ id: "1" });

        expect(result).toEqual({
            id: "1",
            name: "Product 1",
            description: "Product 1 description",
            salesPrice: 10,
        });

    });




});