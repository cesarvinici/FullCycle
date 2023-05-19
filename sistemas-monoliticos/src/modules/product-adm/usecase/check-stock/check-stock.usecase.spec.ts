import Id from "../../../@shared/domain/value-object/id.value-object";
import CheckStockUseCase from "./check-stock.usecase";


const product = {
    id: new Id("1"),
    stock: 10,
    name: "Product 1",
    description: "Product 1 description",
    purchasePrice: 10,
}

const mockProductRepository = () => ({
    add: jest.fn(),
    find: jest.fn().mockReturnValue(Promise.resolve(product)),
});

describe("Check Stock usecase unit tests", () => {

    it("Should check stock", async () => {

        const productRepository = mockProductRepository();
        const checkStockUseCase = new CheckStockUseCase(productRepository);

        const productStock = await checkStockUseCase.execute({ id: "1" });
        
        expect(productRepository.find).toBeCalled();
        expect(productStock.id).toEqual("1");
        expect(productStock.stock).toEqual(10);
    });

});