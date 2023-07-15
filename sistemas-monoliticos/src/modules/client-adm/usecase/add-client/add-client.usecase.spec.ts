import AddClientUsecase from "./add-client.usecase"

const mockRepository = () => ({
    add: jest.fn(),
    find: jest.fn()
})

describe("Add client usecase tests", () => {
    it("Should add a client", async () => {
        const repository = mockRepository()
        const usecase = new AddClientUsecase(repository)
        
        const input = {
            name: "John Doe",
            email: "john@email.com",
            document: "123456789",
            street: "John Doe Street",
            number: "123",
            complement: "ap 123",
            city: "John Doe City",
            state: "John Doe State",
            zipCode: "12345678"
        }

        const result = await usecase.execute(input)

        expect(repository.add).toHaveBeenCalled()
        expect(result.id).toBeDefined()
        expect(result.name).toBe(input.name)
        expect(result.email).toBe(input.email)
        expect(result.document).toBe(input.document)
        expect(result.street).toBe(input.street)
        expect(result.number).toBe(input.number)
        expect(result.complement).toBe(input.complement)
        expect(result.city).toBe(input.city)
        expect(result.state).toBe(input.state)
        expect(result.zipCode).toBe(input.zipCode)
        expect(result.created_at).toBeDefined()
        expect(result.updated_at).toBeDefined()
    })
})