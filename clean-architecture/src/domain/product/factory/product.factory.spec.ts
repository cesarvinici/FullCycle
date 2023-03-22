import Product from "../entity/product";
import ProductB from "../entity/product-b";
import ProductFactory from "./product.factory";

describe("ProductFactory unit tests", () => {

    it("Should create a product type A", () => {
       const product = ProductFactory.create("a", "Product A", 10);

        expect(product).toBeInstanceOf(Product);
        expect(product.id).toBeDefined();
        expect(product.name).toBe("Product A");
        expect(product.price).toBe(10);
        expect(product.constructor.name).toBe("Product");

    });

    it("Should create a product type B", () => {
        const product = ProductFactory.create("b", "Product B", 10);

        expect(product).toBeInstanceOf(ProductB);
        expect(product.id).toBeDefined();
        expect(product.name).toBe("Product B");
        expect(product.price).toBe(20);
        expect(product.constructor.name).toBe("ProductB");

    });

    it("Should throw an error when creating a product with an invalid type", () => {
        expect(() => ProductFactory.create("c", "Product C", 10)).toThrowError(
            "Invalid product type"
        );
    });


});