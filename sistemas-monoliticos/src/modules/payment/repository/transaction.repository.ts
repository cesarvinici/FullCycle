import Id from "../../@shared/domain/value-object/id.value-object";
import Transaction from "../domain/transaction";
import PaymentGatewayInterface from "../gateway/payment.gateway";
import TransactionModel from "./transaction.model";

export default class TransactionRepository implements PaymentGatewayInterface {
    async save(input: Transaction): Promise<Transaction> {
        await TransactionModel.create({
            id: input.id.id,
            orderId: input.orderId,
            amount: input.amount,
            status: input.status,
            createdAt: input.createdAt,
            updatedAt: input.updatedAt
        });

        return new Transaction({
            id: input.id,
            orderId: input.orderId,
            amount: input.amount,
            status: input.status,
            created_at: input.createdAt,
            updated_at: input.updatedAt
        });
    }
}