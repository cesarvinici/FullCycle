import UseCaseInterface from "../../@shared/usecase/use-case.interface";
import InvoiceFacadeInterface, { FindInvoiceFacadeInputDTO, FindInvoiceFacadeOutputDTO, GenerateInvoiceFacadeInputDTO } from "./invoice.facade.interface";

export interface UsecaseProps {
    findInvoiceUseCase: UseCaseInterface;
    generateInvoiceUseCase: UseCaseInterface;
}

export default class InvoiceFacade implements InvoiceFacadeInterface {
    
        private _findInvoiceUseCase: UseCaseInterface;
        private _generateInvoiceUseCase: UseCaseInterface;
    
        constructor(useCasesProps: UsecaseProps) {
            this._findInvoiceUseCase = useCasesProps.findInvoiceUseCase;
            this._generateInvoiceUseCase = useCasesProps.generateInvoiceUseCase;
        }
    
        generateInvoice(input: GenerateInvoiceFacadeInputDTO): Promise<void> {
            return this._generateInvoiceUseCase.execute(input);
        }
    
        findInvoice(input: FindInvoiceFacadeInputDTO): Promise<FindInvoiceFacadeOutputDTO> {
            return this._findInvoiceUseCase.execute(input);
        }
}