import CustomerFactory from "../../../domain/customer/factory/customer.factory";
import Address from "../../../domain/customer/value-object/address";
import ListCustomerUseCase from "./list.customer.usercase";

const customer1 = CustomerFactory.createWithAddress(
    "Customer 1", 
    new Address( "Street 1", 1, "City 1", "State 1", "Zip 1")
);

const customer2 = CustomerFactory.createWithAddress(
    "Customer 2",
    new Address( "Street 2", 2, "City 2", "State 2", "Zip 2")
);

const MockRepository = () => {
    return {
        find: jest.fn(),
        findAll: jest.fn().mockReturnValue(Promise.resolve([customer1, customer2])),
        create: jest.fn(),
        update: jest.fn(),
    }
}

describe("List Customer unit test", () => {
   
    it("should list all customers", async () => {

        const repository = MockRepository();
        const usecase = new ListCustomerUseCase(repository);
        const expectedOutput = await usecase.execute({});
        expect(expectedOutput).toEqual({
            customers: [
                {
                    id: customer1.id,
                    name: customer1.name,
                    address: {
                        street: customer1.address.street,
                        number: customer1.address.number,
                        city: customer1.address.city,
                        state: customer1.address.state,
                        zip: customer1.address.zip,
                    }
                },
                {
                    id: customer2.id,
                    name: customer2.name,
                    address: {
                        street: customer2.address.street,
                        number: customer2.address.number,
                        city: customer2.address.city,
                        state: customer2.address.state,
                        zip: customer2.address.zip,
                    }
                }
            ]
        });


    });
});