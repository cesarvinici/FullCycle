import { Sequelize } from "sequelize-typescript";
import ProductAdmFacadeFactory from "../factory/product.facade.factory";
import { ProductModel } from "../repository/product.model";
import ProductFacadeFactory from "../factory/product.facade.factory";

describe("ProductAdmFacade test", () => {

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

    it("should add a product", async () => {

        const productFacade = ProductFacadeFactory.create();

        const input = {
            id: "1",
            name: "Product 1",
            price: 10
        }

        await productFacade.addProduct(input);

        const product = await ProductModel.findOne({ where: { id: input.id } });

        expect(product).not.toBeNull();
        expect(product.id).toBe(input.id);
        expect(product.name).toBe(input.name);
        expect(product.price).toBe(input.price);
    });
});
