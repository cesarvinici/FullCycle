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
            address: "John's street, 123"
        }

        const result = await usecase.execute(input)

        expect(repository.add).toHaveBeenCalled()
        expect(result.id).toBeDefined()
        expect(result.name).toBe(input.name)
        expect(result.email).toBe(input.email)
        expect(result.address).toBe(input.address)
        expect(result.created_at).toBeDefined()
        expect(result.updated_at).toBeDefined()
    })
})