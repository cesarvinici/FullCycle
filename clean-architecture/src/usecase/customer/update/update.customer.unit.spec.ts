import CustomerFactory from "../../../domain/customer/factory/customer.factory"
import Address from "../../../domain/customer/value-object/address"
import UpdateCustomerUseCase from "./update.customer.usercase"

const customer = CustomerFactory.createWithAddress(
    "Customer 1",
    new Address(
        "Street 1",
        1,
        "City 1",
        "State 1",
        "Zip 1"
    )
)


const input = {
    id: customer.id,
    name: "Customer 1 updated",
    address: {
        street: "Street 1 updated",
        number: 12,
        city: "City 1 updated",
        state: "State 1 updated",
        zip: "Zip 1 updated",
    }
}


const MockRepository = () => {
    return {
        find: jest.fn().mockReturnValue(Promise.resolve(customer)),
        findAll: jest.fn(),
        create: jest.fn(),
        update: jest.fn(),
    }
}

describe("Update Customer unit test", () => {

    it("should update a customer", async () => {
        const repository = MockRepository();
        const usecase = new UpdateCustomerUseCase(repository);
        const expectedOutput = await usecase.execute(input);
        expect(expectedOutput).toEqual(input);
    });

});


