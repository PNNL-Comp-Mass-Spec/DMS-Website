GO

CREATE PROCEDURE <?= $sprocName ?> 
/****************************************************
**
**	Desc: 
**		Adds new or edits existing item in 
**		<?= $table ?> 
**
**	Return values: 0: success, otherwise, error code
**
**	Date: <?= $dt ?> mem - Initial version
**    
*****************************************************/
(
	<?= $args ?>,
	@mode varchar(12) = 'add', -- or 'update'
	@message varchar(512) output,
	@callingUser varchar(128) = ''
)
As
	Set XACT_ABORT, nocount on

	Declare @myError int
	Declare @myRowCount int
	Set @myError = 0
	Set @myRowCount = 0
	
	Set @message = ''

	Declare @msg varchar(256)

	Begin Try 
	
		---------------------------------------------------
		-- Validate input fields
		---------------------------------------------------
	
		If @mode IS NULL OR Len(@mode) < 1
		Begin
			Set @myError = 51002
			RAISERROR ('@mode cannot be blank',
				11, 1)
		End
	
		---------------------------------------------------
		-- Is entry already in database?
		---------------------------------------------------
	
		If @mode = 'add' And Exists (SELECT * FROM  <?= $table ?> WHERE ID = @ID)
		Begin
			-- Cannot create an entry that already exists
			--
			Set @msg = 'Cannot add: item "' + Cast(@ID as varchar(24)) + '" is already in the database'
			RAISERROR (@msg, 11, 1)
			return 51004
		End
		
		
		If @mode = 'update'
		Begin
			Declare @tmp int = 0
			--
			SELECT @tmp = ID
			FROM  <?= $table ?>
			WHERE (ID = @ID)
			--
			SELECT @myError = @@error, @myRowCount = @@rowcount

			If @myError <> 0 OR @tmp = 0
				-- Cannot update a non-existent entry
				Set @msg = 'Cannot update: item "' + Cast(@ID as varchar(24)) + '" is not in the database'
				RAISERROR (@msg, 11, 16)
				return 51005
			End
		End
	
		---------------------------------------------------
		-- Action for add mode
		---------------------------------------------------
		--
		If @Mode = 'add'
		Begin
		
			INSERT INTO <?= $table ?> (
			<?= $cols ?>
			) VALUES (
			<?= $insrts ?> 
			)
			--
			SELECT @myError = @@error, @myRowCount = @@rowcount
			--
			If @myError <> 0
				RAISERROR ('Insert operation failed: "%d"', 11, 7, @ID)
			
			-- Return ID of newly created entry
			-- This method is more accurate than using IDENT_CURRENT
			Set @ID = SCOPE_IDENTITY()
	
		End -- add mode
	
		---------------------------------------------------
		-- Action for update mode
		---------------------------------------------------
		--
		If @Mode = 'update' 
		Begin

			UPDATE <?= $table ?> 
			SET
			<?= $updts ?> 
			WHERE (ID = @ID)
			--
			SELECT @myError = @@error, @myRowCount = @@rowcount
			--
			If @myError <> 0
				RAISERROR ('Update operation failed: "%d"', 11, 4, @ID)
	
		End -- update mode
	
	End Try
	Begin Catch
		EXEC FormatErrorMessage @message output, @myError output
		
		-- Rollback any open transactions
		If (XACT_STATE()) <> 0
			ROLLBACK TRANSACTION;
	End Catch

	return @myError
GO

------------------------------------------------- 
GRANT  EXECUTE  ON <?= $sprocName ?> TO [DMS2_SP_User]
------------------------------------------------- 

