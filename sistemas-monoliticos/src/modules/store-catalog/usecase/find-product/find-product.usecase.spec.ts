import Id from "../../../@shared/domain/value-object/id.value-object";
import Product from "../../domain/product.entity";
import FindProductUsecase from "./find-product.usecase";

const product = new Product({
    id: new Id("1"),
    name: "Product 1",
    description: "Product 1 description",
    salesPrice: 10
})

const mockRepository = () => {
    return {
        findAll: jest.fn(),
        find: jest.fn().mockReturnValue(Promise.resolve(product)),
    }
}
    
describe("Find Product unit test", () => {


    it("Should find a product", async () => {

        const repository = mockRepository();
        const usecase = new FindProductUsecase(repository);

        const product = await usecase.execute({ id: "1" });

        expect(repository.find).toBeCalled();

        expect(product).toEqual({
            id: "1",
            name: "Product 1",
            description: "Product 1 description",
            salesPrice: 10
        });
    });
});