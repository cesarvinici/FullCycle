import { Sequelize } from "sequelize-typescript";
import Customer from "../../../domain/customer/entity/customer";
import Address from "../../../domain/customer/value-object/address";
import CustomerModel from "../../../infrastructure/customer/repository/sequilize/customer.model";
import CustomerRepository from "../../../infrastructure/customer/repository/sequilize/customer.repository";
import FindCustomerUseCase from "./find.customer.usercase";

describe("Find Customer use case", () => {
    
    let sequilize: Sequelize;


    beforeEach(async () => {
        sequilize = new Sequelize({
            dialect: "sqlite",
            storage: ":memory:",
            logging: false,
            sync: { force: true },
        });

        sequilize.addModels([CustomerModel]);
        await sequilize.sync();
    });

    afterEach(async () => {
        await sequilize.close();
    });


    it("should find a customer by id", async () => {

        const customerRepository = new CustomerRepository();
        const useCase = new FindCustomerUseCase(customerRepository);

        const customer = new Customer("1", "Customer 1");
        const address = new Address("Street 1", 1, "City 1", "State 1", "Zip 1");

        customer.changeAddress(address);
        customer.activate();

        await customerRepository.create(customer);

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
});