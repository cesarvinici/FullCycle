import { Sequelize } from "sequelize-typescript";
import TransactionModel from "../repository/transaction.model";
import PaymentFacadeFactory from "./payment.facade.factory";
import Transaction from "../domain/transaction";

describe("PaymentFacade", () => {
    let sequelize: Sequelize;

    beforeEach(async () => {
        sequelize = new Sequelize({
            dialect: "sqlite",
            storage: ":memory:",
            logging: false,
            sync: { force: true },
        });
        sequelize.addModels([TransactionModel])
        await sequelize.sync();
    });

    afterEach(async () => {
        await sequelize.close();
    });


    it("should process a payment", async () => {

        const factory = PaymentFacadeFactory.create();

        const transaction = new Transaction({
            amount: 100,
            orderId: "123",
        })

        const result = await factory.process({
            amount: transaction.amount,
            orderId: transaction.orderId,
        });

        expect(result.transactionId).toBeDefined();
        expect(result.orderId).toEqual(transaction.orderId);
        expect(result.amount).toEqual(transaction.amount);
        expect(result.status).toEqual("approved");
        expect(result.created_at).toBeDefined();
        expect(result.updated_at).toBeDefined();
    });



});