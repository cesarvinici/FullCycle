import Product from "../domain/entity/product";

export default class ProductService {

    static increasePrice(product: Product[], percentage: number): Product[] {
        return product.map(p => {
            p.changePrice(p.price * (1 + percentage / 100));
            return p;
        });
    }
}