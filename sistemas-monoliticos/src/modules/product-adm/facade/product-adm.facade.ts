import UseCaseInterface from "../../@shared/usecase/use-case.interface";
import ProductAdmFacadeInterface, { AddProductAdmFacadeInputDto, CheckStockFacadeInputDto, CheckStockFacadeOutputDto } from "./product-adm.facade.interface";


export interface UseCasesProps {
    addUseCase: UseCaseInterface;
    stockUseCase: UseCaseInterface;
}

export default class ProductAdmFacade implements ProductAdmFacadeInterface {

    private _addUsecase: UseCaseInterface;
    private _checkStockUsecase: UseCaseInterface;


    constructor(useCasesProps: UseCasesProps) {
        this._addUsecase = useCasesProps.addUseCase;
        this._checkStockUsecase = useCasesProps.stockUseCase;
    }

    addProduct(input: AddProductAdmFacadeInputDto): Promise<void> {
        return this._addUsecase.execute(input);
    }

    checkStock(input: CheckStockFacadeInputDto): Promise<CheckStockFacadeOutputDto> {
        return this._checkStockUsecase.execute(input);
    }
}