package application_test

import (
	"go-hexagonal/application"
	mock_application "go-hexagonal/application/mocks"
	"testing"

	"github.com/golang/mock/gomock"
	"github.com/stretchr/testify/require"
)

func TestProductService_Get(t *testing.T) {
	ctr := gomock.NewController(t)
	defer ctr.Finish()

	product := mock_application.NewMockProductInterface(ctr)
	persistence := mock_application.NewMockProductPersistenceInterface(ctr)
	persistence.EXPECT().Get(gomock.Any()).Return(product, nil).AnyTimes()

	service := application.ProductService{Persistence: persistence}

	result, err := service.Get("123")
	require.Nil(t, err)
	require.Equal(t, product, result)

}

func TestProductSerivice_Create(t *testing.T) {
	ctr := gomock.NewController(t)
	defer ctr.Finish()

	product := mock_application.NewMockProductInterface(ctr)
	persistence := mock_application.NewMockProductPersistenceInterface(ctr)
	persistence.EXPECT().Save(gomock.Any()).Return(product, nil).AnyTimes()

	service := application.ProductService{Persistence: persistence}

	result, err := service.Create("Test", 100)
	require.Nil(t, err)
	require.Equal(t, product, result)
}

func TestProductService_EnableDisable(t *testing.T) {
	ctr := gomock.NewController(t)
	defer ctr.Finish()

	product := mock_application.NewMockProductInterface(ctr)
	product.EXPECT().Enable().Return(nil).AnyTimes()
	product.EXPECT().Disable().Return(nil).AnyTimes()

	persistence := mock_application.NewMockProductPersistenceInterface(ctr)
	persistence.EXPECT().Save(gomock.Any()).Return(product, nil).AnyTimes()

	service := application.ProductService{Persistence: persistence}

	result, err := service.Enable(product)
	require.Nil(t, err)
	require.Equal(t, product, result)

	result, err = service.Disable(product)
	require.Nil(t, err)
	require.Equal(t, product, result)

}
