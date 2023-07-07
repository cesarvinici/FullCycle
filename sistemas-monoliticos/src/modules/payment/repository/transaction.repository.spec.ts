import { Sequelize } from "sequelize-typescript";
import TransactionModel from "./transaction.model";
import TransactionRepository from "./transaction.repository";
import Id from "../../@shared/domain/value-object/id.value-object";
import Transaction from "../domain/transaction";

describe("Transaction repository tests", () => {
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


    it("Should add a transaction", async () => {

        const repository = new TransactionRepository();

        const transaction = new Transaction({
            id: new Id("1"),
            orderId: "1",
            amount: 100,
        });

        transaction.approve();

        const savedTransaction = await repository.save(transaction);

        expect(savedTransaction.id.id).toBe("1");
        expect(savedTransaction.orderId).toBe("1");
        expect(savedTransaction.amount).toBe(100);
        expect(savedTransaction.status).toBe("approved");

    });



})