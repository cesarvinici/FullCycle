import { Sequelize } from "sequelize-typescript";
import TransactionModel from "../repository/transaction.model";
import TransactionRepository from "../repository/transaction.repository";
import ProcessPaymentUseCase from "../usecase/process-payment/process-payment.usecase";
import PaymentFacade from "./payment.facade";

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
        // Arrange
        const input = {
            orderId: "1",
            amount: 100,
        };

        const repository = new TransactionRepository();
        const usecase = new ProcessPaymentUseCase(repository);
        const paymentFacade = new PaymentFacade(usecase);

        // Act
        const output = await paymentFacade.process(input);

        // Assert
        expect(output).toEqual({
            transactionId: expect.any(String),
            orderId: input.orderId,
            amount: input.amount,
            status: "approved",
            created_at: expect.any(Date),
            updated_at: expect.any(Date),
        });
    });
})