import Id from "../../../@shared/domain/value-object/id.value-object";
import ProductAdm from "../../domain/product.entity";
import ProductAdmGateway from "../../gateway/product.gateway";
import { AddProductInputDto, AddProductOutputDto } from "./add-product.dto";

export default class AddProductUseCase {

    private _productRepository: ProductAdmGateway;
    
    constructor(_productRepository: ProductAdmGateway) {
        this._productRepository = _productRepository;
    }


    async execute(input: AddProductInputDto): Promise<AddProductOutputDto> {
        
        const props = {
            id: new Id(input.id),
            name: input.name,
            price: input.price
        }

        const product = new ProductAdm(props);

        // Adicionar esse cara / banco, api, arquivo txt
        this._productRepository.add(product);

        return {
            id: product.id.id,
            name: product.name,
            price: product.price,
            createdAt: product.createdAt,
            updatedAt: product.updatedAt
        }
    }

}