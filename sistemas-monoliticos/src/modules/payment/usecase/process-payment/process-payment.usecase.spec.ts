import Id from "../../../@shared/domain/value-object/id.value-object";
import Transaction from "../../domain/transaction";
import ProcessPaymentUseCase from "./process-payment.usecase";

const transaction = new Transaction({
    id: new Id("1"),
    amount: 100,
    orderId: "1",
    status: "approved",
});

const Repository = () => ({
    save: jest.fn().mockReturnValue(transaction)
});

const transactionDeclined = new Transaction({
    id: new Id("1"),
    amount: 99,
    orderId: "1",
    status: "declined",
});

const RepositoryDeclined = () => ({
    save: jest.fn().mockReturnValue(transactionDeclined)
});


describe("ProcessPaymentUsecase", () => {

    it("should process payment", async () => {
        const repository = Repository();
        const usecase = new ProcessPaymentUseCase(repository);

        const input = {
            orderId: "1",
            amount: 100,
        }

        const result = await usecase.execute(input);

        expect(repository.save).toHaveBeenCalled();

        expect(result).toEqual({
            transactionId: "1",
            orderId: "1",
            amount: 100,
            status: "approved",
            created_at: transaction.createdAt,
            updated_at: transaction.updatedAt,
        });
    });

    it("Should decline a transaction", async () => {

        const repository = RepositoryDeclined();
        const usecase = new ProcessPaymentUseCase(repository);

        const input = {
            orderId: "1",
            amount: 99,
        }

        const result = await usecase.execute(input);

        expect(repository.save).toHaveBeenCalled();

        expect(result).toEqual({
            transactionId: "1",
            orderId: "1",
            amount: 99,
            status: "declined",
            created_at: transaction.createdAt,
            updated_at: transaction.updatedAt,
        });
    });


});