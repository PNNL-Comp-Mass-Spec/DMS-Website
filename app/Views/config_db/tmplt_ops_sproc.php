

-------------------------------------------------
GRANT  EXECUTE  ON <?= $sprocName ?> TO DMS_SP_User
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
**  Auth:   mem
**  Date:   <?= $dt ?>
**
** Pacific Northwest National Laboratory, Richland, WA
** Copyright 2009, Battelle Memorial Institute
*****************************************************/
(
    @ID int,
    @mode varchar(12),
    @message varchar(512) output,
    @callingUser varchar(128) = ''
)
As
    Set nocount on

    Declare @myError int
    Declare @myRowCount int
    Set @myError = 0
    Set @myRowCount = 0

    Set @message = ''

    ---------------------------------------------------
    ---------------------------------------------------
    Begin Try

        ---------------------------------------------------
        --
        ---------------------------------------------------

        If @mode = 'delete'
        Begin
            DELETE FROM <?= $table ?> WHERE ID = @ID
            --
            SELECT @myError = @@error, @myRowCount = @@rowcount
            --
            if @myError <> 0
            Begin
                Set @message = 'Delete operation failed'
                RAISERROR (@message, 10, 1)
                return 51007
            End
        End

    ---------------------------------------------------
    ---------------------------------------------------
    End Try
    Begin Catch
        EXEC format_error_message @message output, @myError output

        -- rollback any open transactions
        IF (XACT_STATE()) <> 0
            ROLLBACK TRANSACTION;
    End Catch
    return @myError
