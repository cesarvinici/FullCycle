import Product from "../entity/product";

describe("Product unit tests", () => {

  it("Should throw error when id is empty", () => {
    expect(() => new Product("", "1", 100)).toThrowError("product: Id is required");
  });

  it("Should throw error when name is empty", () => {
    expect(() => new Product("1", "", 100)).toThrowError("product: Name is required");
  });

  it("Should throw error when price is empty", () => {
    expect(() => new Product("1", "1", -1)).toThrowError("product: Price must be greater than zero");
  });


  it("Should throw error for all fields", () => {
    expect(
      () => 
        new Product("", "", -1))
          .toThrowError("product: Id is required, product: Name is required, product: Price must be greater than zero");
  });



  it("Should change name", () => {
    const product = new Product("1", "1", 100);
    product.changeName("2");
    expect(product.name).toBe("2");
  });

  it("Should change price", () => {
    const product = new Product("1", "1", 100);
    product.changePrice(200);
    expect(product.price).toBe(200);
  });
});