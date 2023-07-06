import UseCaseInterface from "../../@shared/usecase/use-case.interface";
import ClientAdmFacadeInterface, { AddClientFacadeInputDTO, FindClientFacadeInputDto, FindClientFacadeOutputDto } from "./client-adm.facade.interface";


export interface UseCasesProps {
    addUseCase: UseCaseInterface;
    findUseCase: UseCaseInterface;
}

export default class ClientAdmFacade implements ClientAdmFacadeInterface {

    private _findUsecase: UseCaseInterface;
    private _addUseCase: UseCaseInterface;

    constructor(useCasesProps: UseCasesProps) {
        this._findUsecase = useCasesProps.findUseCase;
        this._addUseCase = useCasesProps.addUseCase;
    }


    async addClient(input: AddClientFacadeInputDTO): Promise<void> {
        await this._addUseCase.execute(input);
    }
    findClient(input: FindClientFacadeInputDto): Promise<FindClientFacadeOutputDto> {
        return this._findUsecase.execute(input);
    }
    
}