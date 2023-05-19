import FindAllProductsUsecase from "../usecase/find-all-products/find-all-products.usecase";
import FindProductUsecase from "../usecase/find-product/find-product.usecase";
import StoreCatalogFacadeInterface, { FindAllStoreCatalogFacadeOutputDto, FindStoreCatalogFacadeInputDto, FindStoreCatalogFacadeOutputDto } from "./store-catalog.facade.interface";

export interface UseCaseProps {
    findUseCase: FindProductUsecase;
    findAllProductsUseCase: FindAllProductsUsecase;
}


export default class StoreCatalogFacade implements StoreCatalogFacadeInterface {

    private _findUseCase: FindProductUsecase;
    private _findAllProductsUseCase: FindAllProductsUsecase;

    constructor(props: UseCaseProps) { 
        this._findUseCase = props.findUseCase;
        this._findAllProductsUseCase = props.findAllProductsUseCase;
    }


    async findAll(): Promise<FindAllStoreCatalogFacadeOutputDto> {
        return await this._findAllProductsUseCase.execute();
    }

    async find(id: FindStoreCatalogFacadeInputDto): Promise<FindStoreCatalogFacadeOutputDto> {
        return await this._findUseCase.execute(id);
    }
    
}