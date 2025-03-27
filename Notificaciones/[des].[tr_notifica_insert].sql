CREATE TRIGGER [des].[tr_notifica_insert]   
ON  [des].[PLIST_CAB_TIENDA]       
FOR INSERT    AS         

BEGIN TRY
	declare @codpl numeric(9,0)      
	declare @codpack nvarchar(10)      
	declare @fecha datetime           

	select @codpl=CodPL,@fecha=FechaReg    
	from inserted          
	set @codpack=RIGHT('0000000'+CAST(@codpl as nvarchar(7)),7)          

	insert into dbo.notificacion_vigilancia values('Packing List: PL N° '+@codpack,'PT',@codpl,0,@fecha,0)         
END TRY      

BEGIN CATCH      
	ROLLBACK TRANSACTION;      
	THROW;    
END CATCH    