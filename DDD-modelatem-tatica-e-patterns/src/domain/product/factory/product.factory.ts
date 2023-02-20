import Product from "../entity/product";
import ProductB from "../entity/product-b";
import ProductInterface from "../entity/product.interface";

export default class ProductFactory {

    public static create(type: string, name: string, price: number): ProductInterface {
        switch (type) {
            case "a":
                return new Product(this.generateId(), name, price);
            case "b":
                return new ProductB(this.generateId(), name, price);
            default:
                throw new Error("Invalid product type");
        }
    }

    private static generateId(): string {
        return Math.random().toString(36).substr(2, 9);
    }
}