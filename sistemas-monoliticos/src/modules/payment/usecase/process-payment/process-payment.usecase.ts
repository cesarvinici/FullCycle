import UseCaseInterface from "../../../@shared/usecase/use-case.interface";
import Transaction from "../../domain/transaction";
import PaymentGatewayInterface from "../../gateway/payment.gateway";
import { ProcessPaymentInputDTO, ProcessPaymentOutputDTO } from "./process-payment.dto";

export default class ProcessPaymentUseCase implements UseCaseInterface {

    constructor(
        private readonly transactionRepository: PaymentGatewayInterface,
    ) {}


    async execute(input: ProcessPaymentInputDTO): Promise<ProcessPaymentOutputDTO> {
        const transaction = new Transaction({
            amount: input.amount,
            orderId: input.orderId,
        });

        transaction.process();

        const persistTransaction = await this.transactionRepository.save(transaction);

        return {
            transactionId: persistTransaction.id.id,
            orderId: persistTransaction.orderId,
            amount: persistTransaction.amount,
            status: persistTransaction.status,
            created_at: persistTransaction.createdAt,
            updated_at: persistTransaction.updatedAt,
        }
    }

}