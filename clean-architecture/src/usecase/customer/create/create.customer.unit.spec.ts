import CreateCustomerUseCase from "./create.customer.usercase";

const input = {
    name: "Customer 1",
    address: {
        street: "Street 1",
        number: 1,
        city: "City 1",
        state: "State 1",
        zip: "Zip 1",
    }
}

const MockRepository = () => {
    return {
        find: jest.fn(),
        findAll: jest.fn(),
        create: jest.fn(),
        update: jest.fn(),
    }
}

describe("Unit test Create Customer use case", () => {
    it("should create a customer", async () => {
        const customerRepository = MockRepository();
        const useCase = new CreateCustomerUseCase(customerRepository);

        const expectedOutput = {
            id: expect.any(String),
            name: input.name,
            address: {
                street: input.address.street,
                number: input.address.number,
                city: input.address.city,
                state: input.address.state,
                zip: input.address.zip,
            }
        }

        const output = await useCase.execute(input);

        expect(output).toEqual(expectedOutput);
    });

    it("it should throw an error when name is missing", async () => {
        const customerRepository = MockRepository();
        const useCase = new CreateCustomerUseCase(customerRepository);

        const input = {
            name: "",
            address: {
                street: "Street 1",
                number: 1,
                city: "City 1",
                state: "State 1",
                zip: "Zip 1",
            }
        }

        await expect(useCase.execute(input)).rejects.toThrow("Name is required");
    })
});