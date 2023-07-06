import Id from "../../../@shared/domain/value-object/id.value-object"
import Client from "../../domain/client.entity"
import FindClientUsecase from "./find-client.usecase"


const client = new Client({
    id: new Id("123"),
    name: "John Doe",
    email: "john@email.com",
    address: "John's street, 123"
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
            address: client.address,
            created_at: client.createdAt,
            updated_at: client.updatedAt
        })
    })
})
