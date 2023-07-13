import UseCaseInterface from "../../@shared/usecase/use-case.interface";
import ProductFacadeInterface, { AddProductFacadeInputDto } from "./product.facade.interface";


export interface UseCasesProps {
    addUseCase: UseCaseInterface;
}

export default class ProductFacade implements ProductFacadeInterface {

    private _addUsecase: UseCaseInterface;

    constructor(useCasesProps: UseCasesProps) {
        this._addUsecase = useCasesProps.addUseCase;
    }

    addProduct(input: AddProductFacadeInputDto): Promise<void> {
        return this._addUsecase.execute(input);
    }
}