import ProductRepositoryInterface from "../../../domain/product/repository/product-repository.interface";
import { ListProductInputDto, ListProductOutputDto, Product } from "./list.product.dto";

export default class ListProductUsecase {
    private productRepository: ProductRepositoryInterface;

    constructor(productRepository: ProductRepositoryInterface) {
        this.productRepository = productRepository;
    }

    async execute(input: ListProductInputDto): Promise<ListProductOutputDto> {
        const products = await this.productRepository.findAll();

        const productsDto: Product[] = products.map(product => {
            return {
                id: product.id,
                name: product.name,
                price: product.price
            }
        });

        return {
            products: productsDto
        }
    }
}