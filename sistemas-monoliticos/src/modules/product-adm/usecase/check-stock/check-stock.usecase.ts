import ProductAdmGateway from "../../gateway/product-adm.gateway";
import {CheckStockOutputDto, CheckStockInputDto} from "./check-stock.dto";

export default class CheckStockUseCase {

    private _productRepository: ProductAdmGateway;

    constructor(_productRepository: ProductAdmGateway) {
        this._productRepository = _productRepository;
    }

    async execute(input: CheckStockInputDto): Promise<CheckStockOutputDto> {
        const product = await this._productRepository.find(input.id);

        return {
            id: product.id.id,
            stock: product.stock
        }
    }


}