package cli_test

import (
	"fmt"
	cli "go-hexagonal/adapters/cli/product"
	mock_application "go-hexagonal/application/mocks"
	"testing"

	"github.com/golang/mock/gomock"
	"github.com/stretchr/testify/require"
)

func TestRun(t *testing.T) {
	ctrl := gomock.NewController(t)
	defer ctrl.Finish()

	productName := "Product 1"
	productPrice := 10.0
	productStatus := "enabled"
	productId := "1"

	productMock := mock_application.NewMockProductInterface(ctrl)

	productMock.EXPECT().GetID().Return(productId).AnyTimes()
	productMock.EXPECT().GetName().Return(productName).AnyTimes()
	productMock.EXPECT().GetPrice().Return(productPrice).AnyTimes()
	productMock.EXPECT().GetStatus().Return(productStatus).AnyTimes()

	productServiceMock := mock_application.NewMockProductServiceInterface(ctrl)

	productServiceMock.EXPECT().Create(productName, productPrice).Return(productMock, nil).AnyTimes()
	productServiceMock.EXPECT().Get(productId).Return(productMock, nil).AnyTimes()
	productServiceMock.EXPECT().Enable(gomock.Any()).Return(productMock, nil).AnyTimes()
	productServiceMock.EXPECT().Disable(gomock.Any()).Return(productMock, nil).AnyTimes()

	resultExpected := fmt.Sprintf(
		"Created Product: \n\tId: %s \n\tName: %s \n\tPrice: %f \n\tStatus:%s",
		productId, productName, productPrice, productStatus)

	result, err := cli.Run(productServiceMock, "create", "", productName, productPrice)

	require.Nil(t, err)
	require.Equal(t, resultExpected, result)

	resultExpected = fmt.Sprintf("Enabled Product: \n\tId: %s \n\tName: %s \n\tPrice: %f \n\tStatus:%s",
		productId, productName, productPrice, productStatus)

	result, err = cli.Run(productServiceMock, "enable", productId, "", 0)
	require.Nil(t, err)
	require.Equal(t, resultExpected, result)

	resultExpected = fmt.Sprintf("Disabled Product: \n\tId: %s \n\tName: %s \n\tPrice: %f \n\tStatus:%s",
		productId, productName, productPrice, productStatus)

	result, err = cli.Run(productServiceMock, "disable", productId, "", 0)
	require.Nil(t, err)
	require.Equal(t, resultExpected, result)

	resultExpected = fmt.Sprintf("Product: \n\tId: %s \n\tName: %s \n\tPrice: %f \n\tStatus:%s",
		productId, productName, productPrice, productStatus)

	result, err = cli.Run(productServiceMock, "get", productId, "", 0)
	require.Nil(t, err)
	require.Equal(t, resultExpected, result)

}
