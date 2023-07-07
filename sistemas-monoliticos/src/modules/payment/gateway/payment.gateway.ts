import Transaction from "../domain/transaction";

export default interface PaymentGatewayInterface {
    save(input: Transaction): Promise<Transaction>;
}