import ProductFacade from "../facade/product.facade";
import ProductRepository from "../repository/product.repository";
import AddProductUseCase from "../usecase/add-product/add-product.usecase";


export default class ProductFacadeFactory {
    static create() {
        const productRepository = new ProductRepository();
        const addProductUseCase = new AddProductUseCase(productRepository);
        const productFacade = new ProductFacade(
            { addUseCase: addProductUseCase}
        );

        return productFacade;
    }
}