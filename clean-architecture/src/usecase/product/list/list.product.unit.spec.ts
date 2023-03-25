import ListProductUsecase from "./list.product.usecases";


const productList = [
    {
        id: 1,
        name: "Product 1",
        price: 1.00
    }

]

const productMockRepository = () => {
    return {
        list: jest.fn(),
        update: jest.fn(),
        find: jest.fn(),
        findAll: jest.fn().mockReturnValue(Promise.resolve(productList)),
        create: jest.fn()
    }
}

describe("List Product Use Case unit tests", () => {

    it("should list all products", async () => {
        const productRepository = productMockRepository();
        const useCase = new ListProductUsecase(productRepository);

        const expectedOutput = productList;

        const output = await useCase.execute({});

        expect(output).toEqual(
            {
                products: expectedOutput
            }
        );
    });

});