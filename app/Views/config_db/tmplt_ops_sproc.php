GO

CREATE PROCEDURE <?= $sprocName ?>
/****************************************************
**
**  Desc:
**      Performs operation given by @mode on entity given by @ID
**
**  Return values: 0: success, otherwise, error code
**
**  Parameters:
**
**  Auth:   mem
**  Date:   <?= $dt ?>
**
*****************************************************/
(
    @ID int,
    @mode varchar(12),
    @message varchar(512) = '' output,
    @callingUser varchar(128) = ''
)
As
    Set XACT_ABORT, nocount on

    Declare @myError int = 0
    Declare @myRowCount int = 0

    Set @message = ''

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
        -- Action for update mode
        ---------------------------------------------------
        --
        If @mode = 'delete'
        Begin
            DELETE FROM <?= $table ?> WHERE ID = @ID
            --
            SELECT @myError = @@error, @myRowCount = @@rowcount

            If @myError <> 0
            Begin
                Set @message = 'Delete operation failed'
                RAISERROR (@message, 10, 1)
                return 51007
            End
        End

    End Try
    Begin Catch
        EXEC format_error_message @message output, @myError output

        -- Rollback any open transactions
        If (XACT_STATE()) <> 0
            ROLLBACK TRANSACTION;
    End Catch

    return @myError
GO

-------------------------------------------------
GRANT  EXECUTE  ON <?= $sprocName ?> TO DMS2_SP_User
-------------------------------------------------

