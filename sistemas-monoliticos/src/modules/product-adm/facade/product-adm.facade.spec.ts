import { Sequelize } from "sequelize-typescript";
import { ProductAdmModel } from "../repository/product-adm.model";
import ProductAdmFacadeFactory from "../factory/facade.factory";

describe("ProductAdmFacade test", () => {

    let sequelize: Sequelize;

    beforeEach(async () => {
        sequelize = new Sequelize({
            dialect: "sqlite",
            storage: ":memory:",
            logging: false,
            sync: { force: true }
        });
        
        sequelize.addModels([ProductAdmModel])

        await sequelize.sync();
    });

    afterEach(async () => {
        await sequelize.close();
    });

    it("should add a product", async () => {

        const productFacade = ProductAdmFacadeFactory.create();

        const input = {
            id: "1",
            name: "Product 1",
            description: "Product 1 description",
            purchasePrice: 10,
            stock: 10
        }

        await productFacade.addProduct(input);

        const product = await ProductAdmModel.findOne({ where: { id: input.id } });

        expect(product).not.toBeNull();
        expect(product.id).toBe(input.id);
        expect(product.name).toBe(input.name);
        expect(product.description).toBe(input.description);
        expect(product.purchasePrice).toBe(input.purchasePrice);
    });

    it("should check stock", async () => {

        const productFacade = ProductAdmFacadeFactory.create();

        ProductAdmModel.create({
            id: "1",
            name: "Product 1",
            description: "Product 1 description",
            purchasePrice: 10,
            stock: 10,
            createdAt: new Date(),
            updatedAt: new Date()
        });

        const productStock = await productFacade.checkStock({ id: "1" });

        expect(productStock).not.toBeNull();
        expect(productStock.id).toBe("1");
        expect(productStock.stock).toBe(10);
    });

});
