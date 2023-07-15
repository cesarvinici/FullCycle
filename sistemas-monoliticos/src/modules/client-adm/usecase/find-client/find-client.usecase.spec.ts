import Id from "../../../@shared/domain/value-object/id.value-object"
import Client from "../../domain/client.entity"
import FindClientUsecase from "./find-client.usecase"


const client = new Client({
    id: new Id("123"),
    name: "John Doe",
    email: "john@email.com",
    document: "12345678900",
    street: "John Doe Street",
    number: "123",
    complement: "Near the river",
    city: "John Doe City",
    state: "John Doe State",
    zipCode: "12345678"
})

const mockRepository = () => ({
    add: jest.fn(),
    find: jest.fn().mockReturnValue(Promise.resolve(client))
})

describe("Find Client Usecase", () => {
    it("should find a client", async () => {
        const repository = mockRepository();
        const usecase = new FindClientUsecase(repository);

        const input = {
            id: "123"
        }

        const output = await usecase.execute(input);

        expect(repository.find).toHaveBeenCalled();

        expect(output).toEqual({
            id: client.id.id,
            name: client.name,
            email: client.email,
            document: client.document,
            street: client.street,
            number: client.number,
            complement: client.complement,
            city: client.city,
            state: client.state,
            zipCode: client.zipCode,
            created_at: client.createdAt,
            updated_at: client.updatedAt
        })
    })
})
