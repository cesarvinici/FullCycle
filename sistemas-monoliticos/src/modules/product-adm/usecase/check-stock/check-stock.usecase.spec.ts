import CheckStockUseCase from "./check-stock.usecase";

const mockProductRepository = () => ({
    add: jest.fn(),
    find: jest.fn().mockReturnValue({
        id: {
            id: "1"
        },
        stock: 10
    })
});

describe("Check Stock usecase unit tests", () => {

    it("Should check stock", async () => {

        const productRepository = mockProductRepository();
        const checkStockUseCase = new CheckStockUseCase(productRepository);

        const productStock = await checkStockUseCase.execute({ id: "1" });

        expect(productStock.id).toEqual("1");
        expect(productStock.stock).toEqual(10);
    });

});