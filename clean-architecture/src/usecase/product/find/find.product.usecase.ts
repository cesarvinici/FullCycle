import ProductRepositoryInterface from "../../../domain/product/repository/product-repository.interface";
import { findProductInputDto, findProductOutputDto } from "./find.product.dto";

export default class FindProductUsecase {
    private productRepository: ProductRepositoryInterface;

    constructor(productRepository: ProductRepositoryInterface) {
        this.productRepository = productRepository;
    }

    async execute(input: findProductInputDto): Promise<findProductOutputDto> {
        const product = await this.productRepository.find(input.id);
        
        return {
            id: product.id,
            name: product.name,
            price: product.price
        }
    }
}