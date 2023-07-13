import Product from "../domain/product-adm.entity";

export default interface ProductAdmGateway {
    add(product: Product): Promise<void>;
    find(id: string): Promise<Product>;
}