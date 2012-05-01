

------------------------------------------------- 
GRANT  EXECUTE  ON <?= $sprocName ?> TO [DMS_SP_User]
------------------------------------------------- 


CREATE PROCEDURE <?= $sprocName ?> 
/****************************************************
**
**  Desc: 
**    Performs operation given by @mode
**    on entity given by @ID
**
**  Return values: 0: success, otherwise, error code
**
**  Parameters:
**
**    Auth: grk
**    Date: <?= $dt ?> 
**    
** Pacific Northwest National Laboratory, Richland, WA
** Copyright 2009, Battelle Memorial Institute
*****************************************************/
	@ID int,
	@mode varchar(12),
	@message varchar(512) output,
	@callingUser varchar(128) = ''
As
	set nocount on

	declare @myError int
	set @myError = 0

	declare @myRowCount int
	set @myRowCount = 0

	set @message = ''

	---------------------------------------------------
	---------------------------------------------------
	BEGIN TRY 

		---------------------------------------------------
		-- 
		---------------------------------------------------
	
		if @mode = 'delete'
		begin
			DELETE FROM <?= $table ?> WHERE ID = @ID
			--
			SELECT @myError = @@error, @myRowCount = @@rowcount
			--
			if @myError <> 0
			begin
				set @message = 'Delete operation failed'
				RAISERROR (@message, 10, 1)
				return 51007
			end
		end

	---------------------------------------------------
	---------------------------------------------------
	END TRY
	BEGIN CATCH 
		EXEC FormatErrorMessage @message output, @myError output
		
		-- rollback any open transactions
		IF (XACT_STATE()) <> 0
			ROLLBACK TRANSACTION;
	END CATCH
	return @myError
