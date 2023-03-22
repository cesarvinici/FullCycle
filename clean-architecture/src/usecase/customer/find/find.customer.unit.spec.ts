import { Sequelize } from "sequelize-typescript";
import Customer from "../../../domain/customer/entity/customer";
import Address from "../../../domain/customer/value-object/address";
import CustomerModel from "../../../infrastructure/customer/repository/sequilize/customer.model";
import CustomerRepository from "../../../infrastructure/customer/repository/sequilize/customer.repository";
import FindCustomerUseCase from "./find.customer.usercase";

const customer = new Customer("1", "Customer 1");
const address = new Address("Street 1", 1, "City 1", "State 1", "Zip 1");
customer.changeAddress(address);

const MockRepository  = () => {
    return {
        find: jest.fn()
            .mockReturnValue(Promise.resolve(customer)),
        findAll: jest.fn(),
        create: jest.fn(),
        update: jest.fn(),
    }
}


describe("Unit test Find Customer use case", () => {
    
    it("should find a customer by id", async () => {

        const customerRepository = MockRepository();
        const useCase = new FindCustomerUseCase(customerRepository);

        const input = {id: "1"}

        const expectedOutput = {
            id: "1",
            name: "Customer 1",
            address: {
                street: "Street 1",
                number: 1,
                city: "City 1",
                state: "State 1",
                zip: "Zip 1",
            }
        }

        const output = await useCase.execute(input);
        
        expect(output).toEqual(expectedOutput);
    });

    it("should throw an error if customer not found", async () => {

        const customerRepository = MockRepository();
        customerRepository.find.mockReturnValue(Promise.reject(new Error("Customer not found")));
        const useCase = new FindCustomerUseCase(customerRepository);

        const input = {id: "1"}

        await expect(useCase.execute(input)).rejects.toThrow("Customer not found");
    });


});